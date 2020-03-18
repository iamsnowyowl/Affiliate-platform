import { combineReducers } from "redux";
import asesiReducer from "./asesi";
import assessmentsReducer from "./assessments";
import availableAssessmentsReducer from "./avail_assessments";
import articlesReducer from "./articles";
import authReducer from "./auth";
import cartReducer from "./cart";
import multiLanguageReducer from "./multi_language";
import notifications from "./notifications";
import portfolioUmumReducer from "./portfolioUmum";
import portfolioDasarReducer from "./portfolioDasar";
import schemasReducer from "./schemas";
import settingsReducer from "./settings";
import tukReducer from "./tuks";
import userReducer from "./user";
import upnReducer from "./upn";

const rootReducers = combineReducers({
  asesi: asesiReducer,
  assessments: assessmentsReducer,
  availableAssessments: availableAssessmentsReducer,
  articles: articlesReducer,
  auth: authReducer,
  cart: cartReducer,
  multi_language: multiLanguageReducer,
  notifications: notifications,
  portfolioUmum: portfolioUmumReducer,
  portfolioDasar: portfolioDasarReducer,
  schemas: schemasReducer,
  settings: settingsReducer,
  tuk: tukReducer,
  user: userReducer,
  upn: upnReducer
});

export default rootReducers;
