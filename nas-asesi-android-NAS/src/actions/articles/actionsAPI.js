import axios from 'axios';
import constants from '../../constants/constants';
import path from '../../constants/path';
import actionsReducer from './actionsReducer';
import { Digest } from '../../helper/digestHelper';

const actionsAPI = [];

actionsAPI.listArticles = (data, callback) => dispatch => {
  return axios({
    method: 'get',
    url: `${constants.URL}${path.articles(data)}`,
    headers: { 'Content-Type': 'application/json' }
  })
    .then(res => {
      dispatch(actionsReducer.articles(res.data.data));
      callback(res);
    })
    .catch(err => {
      // console.log('error', JSON.stringify(err));
      if (!err.response) {
        callback('err');
      } else {
        callback(err.response);
      }
    });
};

actionsAPI.searchArticles = (data, callback) => dispatch => {
  return axios({
    method: 'get',
    url: `${constants.URL}${path.articles(data)}`,
    headers: { 'Content-Type': 'application/json' }
  })
    .then(res => {
      // console.log('response', res);
      callback(res);
    })
    .catch(err => {
      // console.log('error', JSON.stringify(err));
      if (!err.response) {
        callback('err');
      } else {
        callback(err.response);
      }
    });
};

export default actionsAPI;
