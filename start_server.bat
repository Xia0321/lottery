@echo off
echo Starting PHP Development Server on http://localhost:8080
echo Press Ctrl+C to stop
php -S localhost:8080 -t D:\lottery\web D:\lottery\web\router.php
