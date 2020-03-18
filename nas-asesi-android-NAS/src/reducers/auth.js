import { REHYDRATE } from 'redux-persist/lib/constants';
import constants from '../constants/constants';

const initialState = {};

const authReducer = (state = initialState, action) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.LOGIN:
      return { ...state, ...action.data };
    case constants.LOGOUT:
      return state;
    default:
      return state;
  }
};

export default authReducer;
