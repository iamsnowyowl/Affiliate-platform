const path = {};

//auth
path.login = "/users/login";
path.logout = "/users/logout";
path.signup = "/public/users/applicants";
path.forgotPass = "/public/users/forgot_password";

//tuk
path.getTUKs = "/public/tuks";

//product
path.getSchema = "/public/schemas/views";
path.getProducts = "/products";
path.persyaratanUmum = "/me/persyaratan_umum";

//assessment
path.getAssessments = "/assessments";
path.joinAssessment = assessment_id => "/me/assessments/" + assessment_id;
path.statusAssessment = assessment_id =>
  "/me/assessments/" + assessment_id + "/applicants";
path.portfolios = assessment_id =>
  "/me/assessments/" + assessment_id + "/portfolios";
path.jointRequest = "/join_requests";

//news and updates
path.articles = keyword => "/public/articles?search=" + keyword;
path.article = article_id => "/articles/" + article_id;

path.ME = "/me";
path.fcmToken = token => "/me/refresh_token/" + token;

path.notifications = "/me/notifications";

export default path;
