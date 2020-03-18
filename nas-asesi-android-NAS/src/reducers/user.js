import { REHYDRATE } from 'redux-persist/lib/constants';
import constants from '../constants/constants';

const initialState = {};

const userReducer = (state = initialState, action) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.ME:
      return { ...state, ...action.data };
    default:
      return state;
  }
};

export default userReducer;
