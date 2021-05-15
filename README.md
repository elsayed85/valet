#### 
download valet for windows

```
composer global require cretueusebiu/valet-windows
```

then find the path where the package is installed

```
C:\Users\{Your User here}\AppData\Roaming\Composer\vendor\bin
```

then when you find the path go to config/valet.php and change "default_dir"

```
return [
    "default_dir" => 'C:\Users\INTEL\AppData\Roaming\Composer\vendor\bin'
];

```

then go to the path and overide this file 
```
https://github.com/cretueusebiu/valet-windows/blob/master/cli/valet.php
```
to be
```
https://gist.github.com/elsayed85/b1ac1449099455d3ae9e41bb2e77836e
```


