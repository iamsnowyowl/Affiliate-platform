import hmacSHA512 from 'crypto-js/hmac-sha512';
// import Base64 from 'crypto-js/enc-base64';

export function Digest(path, method) {
  const date = new Date();
  const secret_key = localStorage.getItem('secret_key');
  const userdata = JSON.parse(localStorage.getItem('userdata'));
  const data = method + '+' + path + '+' + date;
  const upn =
    localStorage.getItem('identity_type') === 'username'
      ? userdata.username
      : userdata.email;
  const digest = 'Lsp ' + upn + ':' + btoa(hmacSHA512(data, secret_key));
  //privatekey isinya secretKey dari server,yg dimana itu bakal disimpet di local storage
  return {
    digest: digest,
    date: date,
    method: method,
    path: path
  };
}
