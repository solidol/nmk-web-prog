"""Generate A4 PDFs from lesson and practical-work README.md files using Edge/Chrome."""
from __future__ import annotations

import argparse
import html
import re
import shutil
import subprocess
import sys
import tempfile
from pathlib import Path
from urllib.parse import unquote, urlsplit

ROOT = Path(__file__).resolve().parent
GROUPS = (("Лекціі", "lec-*"), ("Лабораторні", "lab-*"), ("Самостійні", "sr-*"))
CSS = """
@page { size: A4; margin: 18mm 17mm 20mm; }
body { font-family: Arial, 'Segoe UI', sans-serif; font-size: 10.5pt;
       line-height: 1.45; color: #202020; overflow-wrap: anywhere; }
h1 { font-size: 19pt; margin: 0 0 12mm; }
h2 { font-size: 14pt; margin: 8mm 0 3mm; }
h3 { font-size: 12pt; margin: 6mm 0 2mm; }
p, li { orphans: 2; widows: 2; }
li { margin-bottom: 2mm; }
img { display: block; max-width: 100%; max-height: 190mm; height: auto;
      margin: 4mm auto; object-fit: contain; }
table { border-collapse: collapse; width: 100%; margin: 4mm 0; font-size: 9pt; }
th, td { border: 1px solid #888; padding: 2mm; text-align: left;
         vertical-align: top; overflow-wrap: anywhere; }
thead { background: #eee; display: table-header-group; }
tr, img, h1, h2, h3 { break-inside: avoid; }
pre { white-space: pre-wrap; overflow-wrap: anywhere; }
a { color: #174f87; }
"""


def find_browser() -> Path | None:
    for name in ("msedge", "msedge.exe", "chrome", "chrome.exe"):
        found = shutil.which(name)
        if found:
            return Path(found).resolve()
    if sys.platform == "win32":
        import os
        roots = [os.environ.get("PROGRAMFILES(X86)"), os.environ.get("PROGRAMFILES"), os.environ.get("LOCALAPPDATA")]
        for root in filter(None, roots):
            for suffix in ("Microsoft/Edge/Application/msedge.exe", "Google/Chrome/Application/chrome.exe"):
                candidate = Path(root) / suffix
                if candidate.is_file():
                    return candidate.resolve()
    return None


def find_documents(root: Path) -> list[Path]:
    result = []
    for group, pattern in GROUPS:
        parent = root / group
        if parent.is_dir():
            result.extend(folder / "README.md" for folder in sorted(parent.glob(pattern))
                          if folder.is_dir() and (folder / "README.md").is_file())
    return result


def fix_local_urls(body: str, source: Path, output: Path, root: Path) -> str:
    def replace(match: re.Match[str]) -> str:
        attr, quote, escaped_url = match.groups()
        url = html.unescape(escaped_url)
        parsed = urlsplit(url)
        if parsed.scheme or parsed.netloc or url.startswith(("#", "//")) or not parsed.path:
            return match.group(0)
        target = (source.parent / unquote(parsed.path)).resolve()
        if not target.is_file():
            print(f"Попередження: немає локального файлу {url} у {source}", file=sys.stderr)
            return match.group(0)
        if attr.lower() == "href" and target.name.lower() == "readme.md":
            try:
                relative = target.parent.relative_to(root)
            except ValueError:
                relative = None
            if relative is not None and len(relative.parts) == 2 and relative.parts[0] in ("Уроки", "Практичні"):
                target = output / relative / f"{relative.parts[1]}.pdf"
        absolute_url = target.as_uri()
        if parsed.query:
            absolute_url += "?" + parsed.query
        if parsed.fragment:
            absolute_url += "#" + parsed.fragment
        return f'{attr}={quote}{html.escape(absolute_url, quote=True)}{quote}'
    return re.sub(r'''(href|src)\s*=\s*(["'])(.*?)\2''', replace, body,
                  flags=re.IGNORECASE | re.DOTALL)


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--output", type=Path, default=ROOT / "build" / "pdf")
    parser.add_argument("--browser", type=Path, help="Шлях до msedge.exe або chrome.exe")
    args = parser.parse_args()
    try:
        import markdown
    except ImportError:
        print("Встановіть пакет: python -m pip install markdown", file=sys.stderr)
        return 1
    browser = args.browser.resolve() if args.browser else find_browser()
    if not browser or not browser.is_file():
        print("Не знайдено Edge/Chrome. Передайте --browser шлях_до_msedge.exe", file=sys.stderr)
        return 1
    documents = find_documents(ROOT)
    if not documents:
        print("Не знайдено README.md у каталогах Уроки/less-* або Практичні/pract-*.", file=sys.stderr)
        return 1
    output = args.output.resolve()
    failures = 0
    print(f"Браузер: {browser}\nДокументів: {len(documents)}", flush=True)
    with tempfile.TemporaryDirectory(prefix="md-pdf-") as tmp:
        tmpdir = Path(tmp)
        for index, source in enumerate(documents):
            relative = source.parent.relative_to(ROOT)
            destination = output / relative / f"{source.parent.name}.pdf"
            try:
                body = markdown.markdown(source.read_text(encoding="utf-8"),
                                         extensions=["tables", "fenced_code", "sane_lists"])
                body = fix_local_urls(body, source, output, ROOT)
                page = ("<!doctype html><html lang='uk'><head><meta charset='utf-8'>"
                        f"<style>{CSS}</style></head><body>{body}</body></html>")
                html_path = tmpdir / f"document-{index:04d}.html"
                html_path.write_text(page, encoding="utf-8")
                destination.parent.mkdir(parents=True, exist_ok=True)
                # Isolated profile avoids interference with an already-running browser.
                profile = tmpdir / f"profile-{index:04d}"
                command = [str(browser), "--headless", "--disable-gpu", "--no-first-run",
                           "--no-default-browser-check", "--no-pdf-header-footer",
                           "--allow-file-access-from-files", f"--user-data-dir={profile}",
                           f"--print-to-pdf={destination}", html_path.as_uri()]
                completed = subprocess.run(command, capture_output=True, text=True,
                                           encoding="utf-8", errors="replace", timeout=120)
                if completed.returncode != 0 or not destination.is_file() or destination.stat().st_size == 0:
                    raise RuntimeError(completed.stderr.strip() or completed.stdout.strip()
                                       or "Браузер не створив PDF")
                print(f"Створено: {destination.relative_to(output)}", flush=True)
            except (OSError, subprocess.TimeoutExpired, Exception) as error:
                failures += 1
                print(f"Помилка: {source}: {error}", file=sys.stderr, flush=True)
    print(f"Готово. Успішно: {len(documents) - failures}; помилок: {failures}.")
    return 1 if failures else 0


if __name__ == "__main__":
    raise SystemExit(main())
