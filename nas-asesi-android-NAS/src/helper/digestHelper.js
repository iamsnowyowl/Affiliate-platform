import CryptoJS from "crypto-js";
import Base64 from "../helper/Base64";

export function Digest(secret_key, username_email, path, method) {
  const date = Date.now();
  const data = `${method}+${path}+${date}`;

  const authorization =
    "Lsp " +
    username_email +
    ":" +
    Base64.btoa(CryptoJS.HmacSHA512(data, secret_key));

  return {
    authorization: authorization,
    date: date
  };
}
