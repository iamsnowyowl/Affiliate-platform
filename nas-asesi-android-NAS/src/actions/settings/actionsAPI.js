import { Digest } from '../../helper/digestHelper';
import axios from 'axios';
import actionsReducer from './actionsReducer';
import path from '../../constants/path';
import constants from '../../constants/constants';

const actionsAPI = {};

actionsAPI.gantiBahasa = (data, callback) => dispatch => {
  return dispatch(actionsReducer.bahasa(data));
};

actionsAPI.firstTime = (data, callback) => dispatch => {
  return dispatch(actionsReducer.firstTime(data));
};

actionsAPI.fcmToken = (secret_key, username_email, data) => dispatch => {
  const digest = Digest(secret_key, username_email, path.fcmToken(data), 'PUT');
  return axios({
    method: 'put',
    url: constants.URL + path.fcmToken(data),
    data: null,
    headers: {
      Authorization: digest.authorization,
      'X-Lsp-Date': digest.date,
      'Content-Type': 'application/json'
    }
  })
    .then(res => {
      dispatch(actionsReducer.fcmToken(data));
    })
    .catch(err => {
      //error
    });
};

export default actionsAPI;
