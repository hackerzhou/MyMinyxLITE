java -jar yuicompressor-2.4.6.jar --type css --charset utf-8 ../facebox_dev.css -o ../facebox.css
java -jar yuicompressor-2.4.6.jar --type css --charset utf-8 ../style_dev.css -o ../style.css
java -jar yuicompressor-2.4.6.jar --type css --charset utf-8 ../markitup/style_dev.css -o ../markitup/style.css
java -jar yuicompressor-2.4.6.jar --type js --charset utf-8 ../facebox_dev.js -o ../facebox.js
java -jar yuicompressor-2.4.6.jar --type js --charset utf-8 ../comments-ajax_dev.js -o ../comments-ajax.js
java -jar yuicompressor-2.4.6.jar --type js --charset utf-8 ../markitup/jquery.markitup_dev.js -o ../markitup/jquery.markitup.js
./versionTheme.sh
