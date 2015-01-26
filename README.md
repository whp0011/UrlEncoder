# UrlEncoder
url encoder is a securate method for transing message via url(get or post .etc) based on base64 encode
使用url传输用户信息数据时，由于未加密，信息会被截取，可以使用简单的base64加密，然后加入一些干扰码，使别人不能
反base64，从而达到加密url传输的数据。
##基于base64编码
base64_encode()
base64_decode()
##自定义干扰码
define('KEY','abc123');

##自定义加密规则
define('START_NUM',100);
define('STEP_NUM',1);