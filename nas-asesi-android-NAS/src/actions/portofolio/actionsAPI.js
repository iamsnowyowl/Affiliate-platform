import axios from "axios";
import constants from "../../constants/constants";
import path from "../../constants/path";
import actionsReducer from "./actionsReducer";
import { Digest } from "../../helper/digestHelper";

const actionsAPI = {};

actionsAPI.getPortofolioUmum = (
  assessment_id,
  secret_key,
  username_email,
  data,
  callback
) => dispatch => {
  const digest = Digest(
    secret_key,
    username_email,
    path.portfolios(assessment_id),
    "GET"
  );

  return axios({
    method: "get",
    url:
      constants.URL +
      path.portfolios(assessment_id) +
      "?type=UMUM&sort=form_description&limit=50",
    data: data,
    headers: {
      Authorization: digest.authorization,
      "X-Lsp-Date": digest.date,
      "Content-Type": "application/json"
    }
  })
    .then(res => {
      dispatch(actionsReducer.portfolioUmum(res.data.data));
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

actionsAPI.getPortofolioDasar = (
  assessment_id,
  secret_key,
  username_email,
  data,
  callback
) => dispatch => {
  const digest = Digest(
    secret_key,
    username_email,
    path.portfolios(assessment_id),
    "GET"
  );

  return axios({
    method: "get",
    url:
      constants.URL +
      path.portfolios(assessment_id) +
      "?type=DASAR&sort=form_description&limit=50",
    data: data,
    headers: {
      Authorization: digest.authorization,
      "X-Lsp-Date": digest.date,
      "Content-Type": "application/json"
    }
  })
    .then(res => {
      dispatch(actionsReducer.portfolioDasar(res.data.data));
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

actionsAPI.postPortfolios = (
  assessment_id,
  secret_key,
  username_email,
  data,
  callback
) => dispatch => {
  const digest = Digest(
    secret_key,
    username_email,
    path.portfolios(assessment_id),
    "POST"
  );

  return axios({
    method: "post",
    url: constants.URL + path.portfolios(assessment_id),
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

actionsAPI.deletePortfolios = (
  assessment_id,
  applicant_portfolio_id,
  secret_key,
  username_email,
  callback
) => dispatch => {
  const digest = Digest(
    secret_key,
    username_email,
    path.portfolios(assessment_id) + "/" + applicant_portfolio_id,
    "DELETE"
  );

  return axios({
    method: "delete",
    url:
      constants.URL +
      path.portfolios(assessment_id) +
      "/" +
      applicant_portfolio_id,
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
      callback(err);
    });
};

export default actionsAPI;
