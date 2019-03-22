WordPress OSS（简称:WPOSS），基于阿里云OSS对象存储与WordPress实现静态资源到OSS存储。介绍：https://www.laobuluo.com/2186.html

第一、WPOSS插件特点

1、基于WordPress程序且免费提供给用户使用，将网站的静态文件，比如图片、附件，选择存储在阿里云OSS中或者同时在本地和OSS中，提高网站加载速度。 2、我们可选择使用自定义域名，以及支持HTTPS，前提是我们已经在阿里云OSS中设置完毕。

3、我们一起发现插件之美。

第二、WPOSS插件下载

1、插件下载地址

WPOSS插件：https://github.com/laobuluo/wposs

2、安装插件

将插件WPCOS文件夹解压后上传到"wp-content\plugins"目录，然后再网站后台启动插件。

3、插件设置

插件启动之后我们可以在WordPress后台左侧菜单看到"WPOSS设置"，点击设置。

根据我们申请的信息，以及对应的说明文档注释填写。这样，设置完毕之后，我们可以去编辑文章测试看看，上传图片后检查阿里云OSS中是否有对应图片/附件上传进来。阿里云OSS申请，参考： <a href="https://www.laobuluo.com/2228.html">创建阿里云OSS对象存储及自定义域名 附获取Access Key API密钥</a>