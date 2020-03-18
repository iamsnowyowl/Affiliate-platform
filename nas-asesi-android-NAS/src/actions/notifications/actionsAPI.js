import axios from "axios";
import constants from "../../constants/constants";
import path from "../../constants/path";
import actionsReducer from "./actionsReducer";
import { Digest } from "../../helper/digestHelper";

const actionsAPI = {};

actionsAPI.getNotification = (
  secret_key,
  username_email,
  data,
  callback
) => dispatch => {
  const digest = Digest(secret_key, username_email, path.notifications, "GET");

  return axios({
    method: "get",
    url: constants.URL + path.notifications,
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
      // console.log('error', JSON.stringify(err));
      if (!err.response) {
        callback(err);
      } else {
        callback(err.response.data);
      }
    });
};

export default actionsAPI;
