import { REHYDRATE } from "redux-persist/lib/constants";
import constants from "../constants/constants";

const initialState = "";

const upnReducer = (state = initialState, action) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.UPN:
      return (state = action.data);
    default:
      return state;
  }
};

export default upnReducer;
