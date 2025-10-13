```bat
@echo off
echo Content-type: text/html
echo.
echo ^<!DOCTYPE html^>
echo ^<html^>
echo ^<head^>
echo   ^<meta charset="utf-8"^>
echo   ^<title^>Image Text^</title^>
echo ^</head^>
echo ^<body^>
echo ^<h1^>Lab 2^</h1^>
echo ^<p^>Time now %time:~0,-3%^</p^>
echo ^<hr^>
echo ^<p^>Current directory %~dp0^</p^>
echo ^</body^>
echo ^</html^>
```