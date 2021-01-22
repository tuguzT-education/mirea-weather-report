const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

const request = new XMLHttpRequest();
const url = '/scripts/ajax_set_timezone.php';
const params = 'timezone=' + timezone;

request.open('POST', url);
request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
request.send(params);
