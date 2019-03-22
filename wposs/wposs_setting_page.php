<?php
/**
 *  插件设置页面
 * User: zdl25
 * Date: 2019/3/15
 * Time: 17:43
 */
function wposs_setting_page() {
// 如果当前用户权限不足
if (!current_user_can('administrator')) {
	wp_die('Insufficient privileges!');
}

$wposs_options = get_option('wposs_options', True);
if (!empty($_POST)) {
    if($_POST['type'] == 'info_set') {

        foreach ($wposs_options as $k => $v) {
            if ($k =='no_local_file') {
	            $wposs_options[$k] = (isset($_POST[$k])) ? 'true' : 'false';
            } else {
	            $wposs_options[$k] = (isset($_POST[$k])) ? trim(stripslashes($_POST[$k])) : '';
            }
        }
	    // 不管结果变没变，有提交则直接以提交的数据 更新 wposs_options
        update_option('wposs_options', $wposs_options);

        # 更新另外两个wp自带的上传相关属性的值
        # 替换 upload_path 的值
        $upload_path = trim(trim(stripslashes($_POST['upload_path'])), '/');
        update_option('upload_path', ($upload_path == '') ? ('wp-content/uploads') : ($upload_path));
        # 替换 upload_url_path 的值
        update_option('upload_url_path', trim(trim(stripslashes($_POST['upload_url_path'])), '/'));

?>
    <div class="updated"><p><strong>设置已保存！</strong></p></div>

<?php

    }
}

?>


<div class="wrap" style="margin: 10px;">
    <h2>WordPress OSS（WPOSS）阿里云OSS设置</h2>
     <hr/>    
        <p>WordPress OSS（简称:WPOSS），基于阿里云OSS对象存储与WordPress实现静态资源到OSS存储中。提高网站项目的访问速度，以及静态资源的安全存储功能。</p>
        <p>插件网站： <a href="https://www.laobuluo.com" target="_blank">老部落</a> / <a href="https://www.laobuluo.com/2186.html" target="_blank">WPOSS插件发布页面和安装设置教程</a> / 站长交流QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5gBE7Pt" target="_blank"> <font color="red">594467847</font></a>（宗旨：多做事，少说话，效率至上）</p>
        <p>商家促销： <a href="https://www.laobuluo.com/aliyun/" target="_blank">最新阿里云优惠汇总</a></p>
        <p></p>
   
      <hr/>
    <form name="form1" method="post" action="<?php echo wp_nonce_url('./admin.php?page=' . WPOSS_BASEFOLDER . '/wposs_actions.php'); ?>">
        <table class="form-table">
            <tr>
                <th>
                    <legend>Bucket 名称</legend>
                </th>
                <td>
                    <input type="text" name="bucket" value="<?php echo esc_attr($wposs_options['bucket']); ?>" size="50"
                           placeholder="BUCKET"/>

                    <p>我们需要在 <a href="https://oss.console.aliyun.com/overview" target="_blank">阿里云OSS控制台</a> 创建
                        <code>Bucket</code> ，再填写以上内容。 比如：laobuluocom</p>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>EndPoint 地域节点</legend>
                </th>
                <td>
                    <input type="text" name="endpoint" value="<?php echo esc_attr($wposs_options['endpoint']); ?>" size="50"
                           placeholder="oss-cn-shanghai.aliyuncs.com"/>
                    <p>1. 我们在创建Bucket之后，在[概况]中，可以看到 EndPoint 地域节点</p>
                    <p>2. 如果我们的WordPress部署在非阿里云服务器，则输入[外网访问]对应的EndPoint节点</p>
                    <p>3. 如果使用的是[ECS 的经典网络访问]或者[ECS 的 VPC 网络访问]则对应的EndPoint节点</p>
                    
                </td>
            </tr>
            <tr>
                <th>
                    <legend>AccessKey Id</legend>
                </th>
                <td><input type="text" name="accessKeyId" value="<?php echo esc_attr($wposs_options['accessKeyId']); ?>" size="50" placeholder="AccessKeyId"/></td>
            </tr>
            <tr>
                <th>
                    <legend>Access Key Secret</legend>
                </th>
                <td>
                    <input type="text" name="accessKeySecret" value="<?php echo esc_attr($wposs_options['accessKeySecret']); ?>" size="50" placeholder="AccessKeySecret"/>
                </td>
                <p> Access Key API需要我们参考老部落介绍的教程中获取当前账户的API信息，然后填写。</p>
            </tr>
            <tr>
                <th>
                    <legend>不在本地保留备份</legend>
                </th>
                <td>
                    <input type="checkbox"
                           name="no_local_file" <?php if (esc_attr($wposs_options['no_local_file']) == 'true') {
						echo 'checked="TRUE"';
					}
					?> />

                    <p>如果我们只需要将图片等静态文件上传放置OSS中，则勾选；如果我们本地和OSS都存储，那就不勾选。</p>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>当前网站静态目录</legend>
                </th>
                <td>
                    <input type="text" name="upload_path" value="<?php echo get_option('upload_path'); ?>" size="50"
                           placeholder="当前网站静态目录"/>

                    <p>1. 静态文件在当前服务器的位置，例如： <code>wp-content/uploads</code> （不要用"/"开头和结尾），根目录输入<code>.</code>。</p>
                    <p>2. 示范：<code>wp-content/uploads</code></p>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>Bucket 域名</legend>
                </th>
                <td>
                    <input type="text" name="upload_url_path" value="<?php echo get_option('upload_url_path'); ?>" size="50"
                           placeholder="请输入Bucket 域名"/>

                    <p><b>设置注意事项：</b></p>

                    <p>1. 一般我们是以：<code>http://{Bucket域名}/{本地文件夹}</code>，同样不要用"/"结尾。 支持HTTPS。</p>

                    <p>2. <code>{Bucket 域名}</code> 根据实际内网和外网对应选择，如果我们非阿里云服务器则用外网Bucket 域名</p>

                    <p>3. 如果我们自定义域名的，<code>{Bucket 域名}</code> 则需要用到我们自己自定义的域名。</p>
                    <p>4. 示范1： <code>https://laobuluocom.oss-cn-shanghai.aliyuncs.com/wp-content/uploads</code></p>
                    <p>5. 示范2： <code>https://oss.laobuluo.com/wp-content/uploads</code></p>
                </td>
            </tr>
            <tr>
                <th>
                    
                </th>
                <td><input type="submit" name="submit" value="保存WPOSS设置"/></td>
            </tr>
        </table>
        <input type="hidden" name="type" value="info_set">
    </form>
</div>
<?php
}
?>