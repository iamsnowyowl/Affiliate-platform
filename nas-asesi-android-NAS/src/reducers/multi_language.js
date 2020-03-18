import { REHYDRATE } from "redux-persist/lib/constants";
import constants from "../constants/constants";

const initialState = { bahasa: "" };

const multiLanguageReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.MULTI_LANGUANGE:
      return { ...state, ...action.data };
    default:
      return state;
  }
};

export default multiLanguageReducer;
