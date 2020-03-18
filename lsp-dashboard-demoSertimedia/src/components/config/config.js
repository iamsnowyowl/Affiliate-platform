import { Digest } from "../../containers/Helpers/digest";
import lsp_energi from "../../assets/img/brand/lsp-energi.png";
import lsp_gppb from "../../assets/img/brand/Logo-LSP-GPPB.png";
import lsp_abi from "../../assets/img/brand/lsp-abi.png";
import lsp_abiLogin from "../../assets/img/brand/lsp-abi@3x.png";
import lsp_pm from "../../assets/img/brand/logo-LSPPM.png";
import demo from "../../assets/img/brand/nas_landscape.png";

// url
export const apiLocal = "http://192.168.10.10/api/users/login";
export const baseUrl = "http://localhost/NAS_API"; // demo
export const dataSourceTAS =
  "http://sertimedia.com/files/TVE9PQ/205780ec92608f2.jpg";

// path
export const path_forgotPass = "/public/users/forgot_password";
export const path_notif = "/me/notifications";
export const path_users = "/users";
export const path_admin = "/admins";
export const path_management = "/managements";
export const path_tuk = "/public/tuks";
export const path_tukAdd = "/tuks";
export const path_adminTUK = "/admintuk";
export const path_HomeTuk = "/tuk";
export const path_pleno = "/plenos";
export const path_competenceField = "/competence_fields";
export const path_schema = "/schemas";
export const path_schemaViews = "/schemas/views";
export const path_subSchema = "/sub_schemas";
export const path_applicant = "/applicants";
export const path_applicantGeneral = "/users/applicants";
export const path_assessments = "/assessments";
export const path_assessmentsDashboard = "/dashboards/assessments";
export const path_GET_schedule_activity = "/me/schedules/assessments";
export const path_POST_article = "/articles";
export const path_GET_article = "/public/articles";
export const path_assign_assessors = "/find_assessor_not_assign"; //Used in Schedule assessment
export const path_assign_asesi = "/find_applicant_not_assign"; //Used in Schedule assessment
export const path_assign_admins = "/find_admin_not_assign"; //Used in Schedule assessment
export const path_recordArchive = "/schedules/assessments";
export const path_letters = "/letters";
export const path_alumni = "/alumnis";
export const path_certificate = "/certificates";
export const path_accessors = `/accessors`; //GET detail,PUT,DELETE Accessor
export const path_accessorsGeneral = "/users/accessors"; //GET,POST Accessor
export const path_accessorCompetence = "/accessor/competences";
export const path_accessorsSchedule = "/accessor/schedules"; //Used in schedule accessors
export const path_accessorsSkill = "/Assessors/list-skill";
export const path_masterData = "/portfolios";
export const path_refreshToken = "/me/refresh_token/";
export const path_manageSurat = "/letters";
export const path_unitCompetention = "/unit_competences";
export const path_jointRequest = "/join_requests";
export const path_archive = "/archives";
export const path_persyaratanUmum = "/persyaratan_umum";
export const path_persyaratanUmum_otherAsesi = "/persyaratan_umums";
export const path_jobs = "/jobs";
export const path_deletePermanenAssessment = "/deleted/assessments";
export const path_restoreAssessment = "/restore/assessments";

// query fields digunakan untuk GET dari server namun belum tentu di tampilin,
// sedangkan columndef digunankan untuk pendefinisian di datatable
export const query_notif =
  "?datatable=1&fields=is_read,message,data,time_stamp&columndef=is_read,message,data,time_stamp";
export const query_assessments =
  "?datatable=1&fields=assessment_id,title,address,tuk_name,created_date,assessor_id&columndef=assessment_id,title,address,tuk_name,created_date,assessor_id";

// button
export var schedules_accessors =
  '<button type="button" class="btn btn-primary " data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-trash"></i></button>';
export var upload = '<input type="file" onchange="uploadbutton()">';

//m method
export var method_get = "GET";
export var method_post = "POST";
export var method_put = "PUT";
export var method_delete = "DELETE";

//get Role
export function getRole() {
  const json = JSON.parse(localStorage.getItem("userdata"));
  const role = json.role_code;

  return role;
}

//get Language
export const getLanguage = localStorage.getItem("bahasa");

// format capitalize first letter
export function Capital(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

// fungsi untuk membuat ... pada kalimat yang melebihi batas
String.prototype.trunc =
  String.prototype.trunc ||
  function(n) {
    return this.length > n
      ? this.substr(0, n - 1) +
          "..." +
          this.substr(this.length - 6, this.length)
      : this;
  };

//minimize string
export function minimizeString(value) {
  var str = value;

  return str.trunc(7);
}

// Format Date
export function formatDate(value) {
  const date = new Date(value);
  var month = [];
  month[0] = "January";
  month[1] = "February";
  month[2] = "March";
  month[3] = "April";
  month[4] = "May";
  month[5] = "June";
  month[6] = "July";
  month[7] = "August";
  month[8] = "September";
  month[9] = "October";
  month[10] = "November";
  month[11] = "December";
  var d = month[date.getMonth()];
  var day = date.getDate();
  var year = date.getFullYear();
  return day + " " + d + " " + year;
}

// Format Upper Lower Case
export function formatCapitalize(value) {
  var text = value;
  if (value !== undefined) {
    text = text
      .toLowerCase()
      .split(" ")
      .map(item => item.charAt(0).toUpperCase() + item.substring(1))
      .join(" ");
  } else {
    text = value;
  }

  return text;
}

//regex clear underscore
export function clearUnderscore(value) {
  var text = value.replace(/_/g, " ");

  return text;
}

// getData
export function getData(path, method, data) {
  const auth = Digest(path, method);
  var link = baseUrl + path;

  const options = {
    method: auth.method,
    headers: {
      Authorization: auth.digest,
      "X-Lsp-Date": auth.date,
      "Content-Type": "multipart/form-data"
    },
    url: link,
    data: data
  };

  return options;
}

export function permission() {
  var local = localStorage.getItem("permission");
  var arrayPermission = local.split(",");

  return arrayPermission;
}

export function createPermission(item) {
  return permission().some(value => value === item + "_CREATE");
}

export function listPermission(item) {
  return permission().some(value => value === item + "_LIST");
}

export function updatePermission(item) {
  return permission().some(value => value === item + "_UPDATE");
}

export function deletePermission(item) {
  return permission().some(value => value === item + "_DELETE");
}

export function downloadFile(path, method) {
  const auth = Digest(path, method);
  const options = {
    method: auth.method,
    headers: {
      Authorization: auth.digest,
      "X-Lsp-Date": auth.date
    },
    url: baseUrl + path,
    responseType: "blob"
  };

  return options;
}

// Config Logo

export const Brand_LSP = lsp_name => {
  var Logo = "";
  var title = "";
  if (lsp_name === "lsp_energi") {
    Logo = lsp_energi;
    title = "LSP ENERGI";
    return { Logo, title };
  } else if (lsp_name === "lsp_gppb") {
    Logo = lsp_gppb;
    title = "LSP GPPB";
  } else if (lsp_name === "demo") {
    Logo = demo;
    title = "NAS Application";
  } else if (lsp_name === "lsp_abi") {
    Logo = lsp_abi;
    title = "LSP ABI";
  } else if (lsp_name === "lsp_abiLogin") {
    Logo = lsp_abiLogin;
    title = "LSP ABI";
  } else if (lsp_name === "lsp_pm") {
    Logo = lsp_pm;
    title = "LSP PM";
  }

  return { Logo, title };
};

// get params query string

export const parseParamsURLquery = value => {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  return urlParams.get(value);
};

// delete url query string
export function deleteQueryString(url) {
  return url.split("?")[0];
}

// response interceptors
// export const errorHandler = (error) => {
//   if (isHandlerEnabled(error.config)) {
//     // Handle errors
//   }
//   return Promise.reject({ ...error })
// }

// export const successHandler = (response) => {
//   if (isHandlerEnabled(response.config)) {
//     // Handle responses
//   }
//   return response
// }
