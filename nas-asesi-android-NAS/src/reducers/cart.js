import { REHYDRATE } from 'redux-persist/lib/constants';
import constants from '../constants/constants';

const initialState = { gotoCart: false, orders: [] };

const deleteById = (state, id) => {
  let newState = state.orders.filter(item => item.orderId !== id);
  delete state['orders'];
  state['orders'] = newState;
  return state;
};

const addNew = (state, data) => {
  let { orders } = state;
  orders.push(data);
  return state;
};

const cartReducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case REHYDRATE:
      return state;
    case constants.ADD_TO_CART:
      return addNew(state, action.data);
    case constants.UPDATE_CART:
      const updatedItem = state.map(item => {
        if (item.orderId === action.data.orders.orderId) {
          return { ...item, ...action.data };
        }
        return item;
      });
      return updatedItem;
    case constants.REMOVE_FROM_CART:
      return deleteById(state, action.data.orders.orderId);
    case constants.GOTO_CART:
      return { ...state, ...action.data };
    default: {
      return state;
    }
  }
};

export default cartReducer;
