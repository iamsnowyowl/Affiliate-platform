import axios from "axios";
import constants from "../../constants/constants";
import path from "../../constants/path";
import actionsReducer from "./actionsReducer";
import { Digest } from "../../helper/digestHelper";

const actionsAPI = {};

actionsAPI.getProducts = (
  secret_key,
  username_email,
  data,
  callback
) => dispatch => {
  const digest = Digest(secret_key, username_email, path.getProducts, "GET");
  return axios({
    method: "get",
    url: `${constants.URL}${path.getProducts}`,
    data: data,
    headers: {
      Authorization: digest.authorization,
      "X-Lsp-Date": digest.date,
      "Content-Type": "application/json"
    }
  })
    .then(res => {
      dispatch(actionsReducer.products(res.data.data));
      // console.log('products', res.data);
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

actionsAPI.getSchema = (keyword, limit, offset, callback) => dispatch => {
  return axios({
    method: "get",
    url: `${constants.URL}${path.getSchema}?search=${keyword}&limit=${limit}&offset=${offset}`,
    headers: {
      "Content-Type": "application/json"
    }
  })
    .then(res => {
      // dispatch(actionsReducer.schemas(res));
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

actionsAPI.getTUKs = (data, callback) => dispatch => {
  return axios({
    method: "get",
    url: `${constants.URL}${path.getTUKs}`,
    data: data,
    headers: { "Content-Type": "application/json" },
    timeout: 10000
  })
    .then(res => {
      dispatch(actionsReducer.tuks(res.data.data));
      callback(res);
    })
    .catch(err => {
      if (!err.response) {
        callback(err);
      } else {
        callback(err.response.dat);
      }
    });
};

actionsAPI.getJobs = (keyword, callback) => dispatch => {
  return axios({
    method: "get",
    url: `${constants.URL}/public/jobs?search=${keyword}`,
    headers: { "Content-Type": "application/json" }
  })
    .then(res => {
      callback(res);
    })
    .catch(err => {
      callback(err);
    });
};

actionsAPI.getPersyaratanUmum = (data, callback) => dispatch => {
  const digest = Digest(
    data.secret_key,
    data.username_email,
    path.persyaratanUmum,
    "GET"
  );
  return axios({
    method: "get",
    url: `${constants.URL}${path.persyaratanUmum}?sort=form_description`,
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

actionsAPI.postPersyaratanUmum = (
  secret_key,
  username_email,
  data,
  callback
) => dispatch => {
  const digest = Digest(
    secret_key,
    username_email,
    path.persyaratanUmum,
    "POST"
  );
  return axios({
    method: "post",
    url: `${constants.URL}${path.persyaratanUmum}`,
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
      callback(err);
    });
};

actionsAPI.deletePersyaratanUmum = (
  secret_key,
  username_email,
  persyaratan_id,
  callback
) => dispatch => {
  const digest = Digest(
    secret_key,
    username_email,
    `/persyaratan_umums/${persyaratan_id}`,
    "DELETE"
  );
  return axios({
    method: "delete",
    url: `${constants.URL}/persyaratan_umums/${persyaratan_id}`,
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

actionsAPI.unitCompetences = (
  secret_key,
  username_email,
  sub_schema_number,
  callback
) => dispatch => {
  const digest = Digest(secret_key, username_email, "/unit_competences", "GET");
  return axios({
    method: "get",
    url: `${constants.URL}/unit_competences?sub_schema_number=${sub_schema_number}`,
    headers: {
      Authorization: digest.authorization,
      "X-Lsp-Date": digest.date,
      "Content-Type": "application/json"
    }
  })
    .then(res => {
      // console.log("success", res);
      callback(res);
    })
    .catch(err => {
      // console.log("error", JSON.stringify(err));
      if (!err.response) {
        callback("err");
      } else {
        callback(err.response);
      }
    });
};

export default actionsAPI;
