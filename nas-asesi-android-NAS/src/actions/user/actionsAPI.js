import axios from "axios";
import constants from "../../constants/constants";
import path from "../../constants/path";
import actionsReducer from "./actionsReducer";
import AsyncStorage from "@react-native-community/async-storage";
import { Digest } from "../../helper/digestHelper";

const actionsAPI = {};

actionsAPI.getUser = (
  secret_key,
  username_email,
  data,
  callback
) => dispatch => {
  const digest = Digest(secret_key, username_email, path.ME, "GET");
  return axios({
    method: "get",
    url: `${constants.URL}${path.ME}`,
    headers: {
      Authorization: digest.authorization,
      "X-Lsp-Date": digest.date,
      "Content-Type": "application/json"
    }
  })
    .then(res => {
      if (res.data.data != null) {
        dispatch(actionsReducer.me(res.data.data));
      }
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

actionsAPI.updateProfile = (
  picture,
  secret_key,
  username_email,
  data,
  callback
) => dispatch => {
  const paths = picture == false ? path.ME : "/me/picture";
  const url =
    picture == false
      ? `${constants.URL}${path.ME}`
      : constants.URL + "/me/picture";
  const digest = Digest(secret_key, username_email, paths, "PUT");
  return axios({
    method: "put",
    url: url,
    data: data,
    headers: {
      Authorization: digest.authorization,
      "X-Lsp-Date": digest.date,
      "Content-Type": "application/json"
    }
  })
    .then(res => {
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

export default actionsAPI;
