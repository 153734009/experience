<?php
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
它具体的作用就是去验证对方提供的（读取https）证书是否有效，过期，或是否通过CA颁发的！
在Windows下，curl找不到CA证书去验证对方的证书！
也可指定CA的存放位置
curl_setopt($ch, CURLOPT_CAINFO, 'E:\path\to\curl-ca-bundle.crt');

