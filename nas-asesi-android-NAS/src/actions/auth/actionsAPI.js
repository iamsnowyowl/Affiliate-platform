import axios from "axios";
import constants from "../../constants/constants";
import path from "../../constants/path";
import actionsReducer from "./actionsReducer";
import AsyncStorage from "@react-native-community/async-storage";
import { Digest } from "../../helper/digestHelper";

const actionsAPI = {};

actionsAPI.login = (data, callback) => dispatch => {
  return axios({
    method: "post",
    url: `${constants.URL}${path.login}`,
    data: data,
    timeout: 10000,
    headers: {
      "Content-Type": "application/json"
    }
  })
    .then(res => {
      AsyncStorage.setItem(constants.SECRET_KEY, res.data.secret_key);
      // AsyncStorage.setItem(constants.USERNAME_EMAIL, res.data.data.username);
      dispatch(actionsReducer.login(res.data));
      dispatch(actionsReducer.upn(data.username_email));

      const { permission } = res.data.data;
      let newArray = [];
      for (let i = 0; i < permission.length; i++) {
        newArray.push(permission[i].sub_module_code);
      }

      dispatch(actionsReducer.permission(newArray));
      callback(res);
    })
    .catch(err => {
      if (!err.response) {
        callback("err");
      } else {
        callback(err.response);
      }
    });
};

actionsAPI.logout = (
  secret_key,
  username_email,
  data,
  callback
) => dispatch => {
  const digest = Digest(secret_key, username_email, path.logout, "POST");
  return axios({
    method: "post",
    url: `${constants.URL}${path.logout}`,
    data: data,
    headers: {
      Authorization: digest.authorization,
      "X-Lsp-Date": digest.date,
      "Content-Type": "application/json"
    }
  })
    .then(res => {
      dispatch(actionsReducer.logout());
      callback(res);
    })
    .catch(err => {
      if (!err.response) {
        callback("err");
      } else {
        callback(err.response);
      }
    });
};

actionsAPI.signup = (data, callback) => dispatch => {
  return axios({
    method: "post",
    url: `${constants.URL}${path.signup}`,
    data: data,
    timeout: 8000,
    headers: { "Content-Type": "application/json" }
  })
    .then(res => {
      // dispatch();
      callback(res);
    })
    .catch(err => {
      if (!err.response) {
        callback("err");
      } else {
        callback(err.response);
      }
    });
};

actionsAPI.forgotPass = (data, callback) => dispatch => {
  return axios({
    method: "post",
    url: `${constants.URL}${path.forgotPass}`,
    data: data,
    timeout: 8000,
    headers: { "Content-Type": "application/json" }
  })
    .then(res => {
      // dispatch();
      callback(res);
    })
    .catch(err => {
      if (!err.response) {
        callback(err);
      } else {
        callback(err.response.data);
      }
    });
};

export default actionsAPI;
