import asesi from './asesi';
import assessments from './asesmen';
import articles from './articles';
import auth from './auth';
import cart from './cart';
import discover from './discover';
import notifications from './notifications';
import portfolio from './portofolio';
import user from './user';
import settings from './settings';

const actionsAPI = {
  asesi: asesi.actionsAPI,
  assessments: assessments.actionsAPI,
  articles: articles.actionsAPI,
  auth: auth.actionsAPI,
  cart: cart.actionsAPI,
  discover: discover.actionsAPI,
  notifications: notifications.actionsAPI,
  portfolio: portfolio.actionsAPI,
  user: user.actionsAPI,
  settings: settings.actionsAPI
};

const actionsReducer = {
  asesi: asesi.actionsReducer,
  assessments: settings.actionsReducer,
  articles: articles.actionsReducer,
  auth: auth.actionsReducer,
  cart: cart.actionsReducer,
  discover: discover.actionsReducer,
  notifications: notifications.actionsReducer,
  portfolio: portfolio.actionsReducer,
  user: user.actionsReducer,
  settings: settings.actionsReducer
};

const actions = {
  actionsAPI: actionsAPI,
  actionsReducer: actionsReducer
};

export default actions;
