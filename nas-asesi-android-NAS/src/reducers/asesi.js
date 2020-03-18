import { REHYDRATE } from 'redux-persist/lib/constants';
import constants from '../constants/constants';

const initialState = { listAsesi: [] };

const deleteById = (state, id) => {
  let newState = state.listAsesi.filter(item => item.id !== id);
  delete state['listAsesi'];
  state['listAsesi'] = newState;
  return state;
};

const addNew = (state, data) => {
  let { listAsesi } = state;
  listAsesi.push(data);
  return state;
};

const asesiReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.ADD_ASESI:
      return addNew(state, action.data);
    case constants.UPDATE_ASESI:
      const updatedItem = state.map(item => {
        if (item.id === action.data.id) {
          return { ...item, ...action.data };
        }
        return item;
      });
      return updatedItem;
    case constants.REMOVE_ASESI:
      return deleteById(state, action.data.asesi.id);
    default: {
      return state;
    }
  }
};

export default asesiReducer;
