set root=D:\www_root\blog\wp-content\themes\MyMinyxLITE
copy /B /Y %root%\compress\version.txt + %root%\style.css %root%\style.temp.css
move /Y %root%\style.temp.css %root%\style.css
