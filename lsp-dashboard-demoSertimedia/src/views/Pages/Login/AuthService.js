import React, {Component} from 'react';
import hmacSHA512 from 'crypto-js/hmac-sha512';
import Base64 from 'base-64';

class AuthService extends Component{
  constructor(props) {
    super(props);
    this.getHeader=this.getHeader.bind(this);
  }

  getHeader(){
    const header = {};
      const method = "POST";
      const path = "/users";
      const date = new Date();
    // if(SessionManager.isLogin()){
      const data = method+'+'+path+'+'+date;
      const hmacDigest = Base64.stringify(hmacSHA512(data, "abcde"));
    //   //privatekey isinya secretKey dari server,yg dimana itu bakal disimpet di local storage
    // }
    return hmacDigest;
  }
  
  render() {

    return (
      <h1>{this.getHeader()}</h1>
    );
  }
}

export default AuthService;
