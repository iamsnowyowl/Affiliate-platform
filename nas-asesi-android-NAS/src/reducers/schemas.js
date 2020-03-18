import { REHYDRATE } from "redux-persist/lib/constants";
import constants from "../constants/constants";

const initialState = [];

const schemaReducer = (state = initialState, action) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.SCHEMAS:
      return { ...state, ...action.data };
    default:
      return state;
  }
};

export default schemaReducer;
