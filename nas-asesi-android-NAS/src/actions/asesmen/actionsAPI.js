import axios from "axios";
import constants from "../../constants/constants";
import path from "../../constants/path";
import actionsReducer from "./actionsReducer";
import { Digest } from "../../helper/digestHelper";

const actionsAPI = {};

actionsAPI.getAssessments = (
  secret_key,
  username_email,
  data,
  callback
) => dispatch => {
  const digest = Digest(
    secret_key,
    username_email,
    "/me" + path.getAssessments,
    "GET"
  );
  return axios({
    method: "get",
    url: `${constants.URL}/me${path.getAssessments}?last_activity_state=ADMIN_CONFIRM_FORM,PORTFOLIO_APPLICANT_COMPLETED,ASSESSOR_READY,ADMIN_READY,ON_REVIEW_APPLICANT_DOCUMENT,ON_COMPLETED_REPORT,REAL_ASSESSMENT,PLENO_MEMBER_READY,PLENO_DOCUMENT_COMPLETED,PLENO_REPORT_READY,REQUEST_BLANKO_SENDING,PRINT_CERTIFICATE,COMPLETED`,
    headers: {
      Authorization: digest.authorization,
      "X-Lsp-Date": digest.date,
      "Content-Type": "application/json"
    }
  })
    .then(res => {
      dispatch(actionsReducer.assessments(res.data.data));
      callback(res.data.data);
    })
    .catch(err => {
      if (!err.response) {
        callback(err);
      } else {
        callback(err.response);
      }
    });
};

actionsAPI.searchAssessments = (
  secret_key,
  username_email,
  data,
  callback
) => dispatch => {
  const digest = Digest(
    secret_key,
    username_email,
    "/me" + path.getAssessments,
    "GET"
  );
  return axios({
    method: "get",
    url: `${constants.URL}/me${path.getAssessments +
      "?search=" +
      data}&last_activity_state=ADMIN_CONFIRM_FORM,PORTFOLIO_APPLICANT_COMPLETED,ASSESSOR_READY,ADMIN_READY,ON_REVIEW_APPLICANT_DOCUMENT,ON_COMPLETED_REPORT,REAL_ASSESSMENT,PLENO_MEMBER_READY,PLENO_DOCUMENT_COMPLETED,PLENO_REPORT_READY,REQUEST_BLANKO_SENDING,PRINT_CERTIFICATE,COMPLETED`,
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

actionsAPI.getAvailableAssessments = (
  secret_key,
  username_email,
  keyword,
  tuk_id,
  callback
) => dispatch => {
  const digest = Digest(secret_key, username_email, path.getAssessments, "GET");
  return axios({
    method: "get",
    url: `${constants.URL}${path.getAssessments +
      "?search=" +
      keyword +
      "&last_activity_state=ADMIN_CONFIRM_FORM,ON_REVIEW_APPLICANT_DOCUMENT"}&tuk_id=${tuk_id}`,
    headers: {
      Authorization: digest.authorization,
      "X-Lsp-Date": digest.date,
      "Content-Type": "application/json"
    }
  })
    .then(res => {
      // console.log(res);
      dispatch(actionsReducer.avail_assessments(res.data.data));
      callback(res);
    })
    .catch(err => {
      // console.log(JSON.stringify(err));
      if (!err.response) {
        callback(err);
      } else {
        callback(err.response);
      }
    });
};

actionsAPI.joinAssessment = (
  secret_key,
  username_email,
  assessment_id,
  callback
) => dispatch => {
  const digest = Digest(
    secret_key,
    username_email,
    path.joinAssessment(assessment_id),
    "POST"
  );
  return axios({
    method: "post",
    url: constants.URL + path.joinAssessment(assessment_id),
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
      if (JSON.stringify(err.message).includes("409")) {
        callback("409");
      } else {
        callback(err);
      }
    });
};

actionsAPI.getStatusAssessment = (
  secret_key,
  username_email,
  assessment_id,
  callback
) => dispatch => {
  const digest = Digest(
    secret_key,
    username_email,
    path.statusAssessment(assessment_id),
    "GET"
  );
  return axios({
    method: "get",
    url: constants.URL + path.statusAssessment(assessment_id),
    headers: {
      Authorization: digest.authorization,
      "X-Lsp-Date": digest.date,
      "Content-Type": "application/json"
    }
  })
    .then(res => {
      // console.log(res);
      callback(res);
    })
    .catch(err => {
      // console.log(JSON.stringify(err));
      if (!err.response) {
        callback("err");
      } else {
        callback(err.response);
      }
    });
};

actionsAPI.joinRequest = (
  secret_key,
  username_email,
  data,
  callback
) => dispatch => {
  const digest = Digest(secret_key, username_email, path.jointRequest, "POST");
  return axios({
    method: "post",
    url: `${constants.URL}${path.jointRequest}`,
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
