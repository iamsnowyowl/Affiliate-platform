import { REHYDRATE } from "redux-persist/lib/constants";
import constants from "../constants/constants";

const initialState = [];

const assessmentsReducer = (state = initialState, action) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.ASSESSMENTS:
      return (state = action.data);
    default:
      return state;
  }
};

export default assessmentsReducer;
