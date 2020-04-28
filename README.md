
WordPress阿里云OSS对象存储插件（WPOSS）
----------------------------

WordPress OSS（简称:WPOSS），基于阿里云OSS对象存储与WordPress实现静态资源到OSS存储。介绍：https://www.laobuluo.com/2250.html

基于WordPress程序，可以选择本地+OSS存储静态资源或者单独使用OSS存储，可以自定义域名或者是使用阿里云自带的Bucket域名。如果我们觉得喜欢，可以一起看看吧。

--------------------

**第一、官方网站和更新**

1、发布者老蒋：https://www.itbulu.com/
2、站长交流QQ群： 1012423279（网站运营及互联网创业交流）
3、最新阿里云优惠汇总：https://www.laobuluo.com/aliyun/

**第二、WPOSS插件特点**

1、基于WordPress程序且免费提供给用户使用，将网站的静态文件，比如图片、附件，选择存储在阿里云OSS中或者同时在本地和OSS中，提高网站加载速度。
2、我们可选择使用自定义域名，以及支持HTTPS，前提是我们已经在阿里云OSS中设置完毕。

3、我们一起发现插件之美。

4、插件更多详细介绍和安装：https://www.laobuluo.com/2250.html

**第三、WPOSS插件下载**

1、插件下载地址

WP平台下载：https://wordpress.org/plugins/wposs

2、安装插件

将插件WPOSS文件夹解压后上传到"wp-content\plugins"目录，然后再网站后台启动插件。

![请输入图片描述][1]

3、插件设置

插件启动之后我们可以在WordPress后台左侧菜单看到"WPOSS设置"，点击设置。

![请输入图片描述][2]

根据我们申请的信息，以及对应的说明文档注释填写。这样，设置完毕之后，我们可以去编辑文章测试看看，上传图片后检查阿里云OSS中是否有对应图片/附件上传进来。阿里云OSS申请，参考：[创建阿里云OSS对象存储及自定义域名 附获取Access Key API密钥][3]


**WPOSS插件更新**

= 0.1 =
* 1、WPOSS正式发布。
* 2、本插件经过几周的测试，支持最新的WordPress程序，现予以发布。

= 0.2 =
* 1、根据WP官方发布要求进行修改函数匹配和安全。
* 2、第一次提交WP官方平台，需要修改适配WP官方插件要求。

= 0.3 =
* 1、修复"停用"和"插件"后恢复原WP的目录文件夹
* 2、解决用户安装与主题冲突空白问题

= 1.0.1 =
* 1、通过1年测试WPCOS比较稳定正式版本发布
* 2、重构CSS样式极简风格
* 3、兼容WordPress5.4版本
* 4、计划重构核心代码提高上传速度


  [1]: https://raw.githubusercontent.com/laobuluo/wposs/master/wpoos-1-1.jpg
  [2]: https://raw.githubusercontent.com/laobuluo/wposs/master/wpoos-1-2.jpg
  [3]: https://www.laobuluo.com/2250.html