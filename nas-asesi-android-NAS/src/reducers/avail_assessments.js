import { REHYDRATE } from "redux-persist/lib/constants";
import constants from "../constants/constants";

const initialState = [];

const availableAssessmentsReducer = (state = initialState, action) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.AVAIL_ASSESSMENTS:
      return (state = action.data);
    default:
      return state;
  }
};

export default availableAssessmentsReducer;
