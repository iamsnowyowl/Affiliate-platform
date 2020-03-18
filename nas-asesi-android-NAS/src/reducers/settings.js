import { REHYDRATE } from "redux-persist/lib/constants";
import { NativeModules } from "react-native";
import constants from "../constants/constants";

const locale = NativeModules.I18nManager.localeIdentifier;

const initialState = {
  first_time: true,
  bahasa: locale == "en_US" ? "en" : "id",
  fcm_token: "",
  permission: []
};

const multiLanguageReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.FIRST_TIME:
      return { ...state, ...action.data };
    case constants.MULTI_LANGUANGE:
      return { ...state, ...action.data };
    case constants.FCM_TOKEN:
      return { ...state, ...action.data };
    case constants.PERMISSION:
      return { ...state, ...action.data };
    default:
      return state;
  }
};

export default multiLanguageReducer;
