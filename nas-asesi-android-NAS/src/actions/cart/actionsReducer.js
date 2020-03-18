import constants from '../../constants/constants';

const actionsReducer = {};

actionsReducer.goto = data => ({
  type: constants.GOTO_CART,
  data: { gotoCart: data }
});

actionsReducer.get = data => ({
  type: constants.GET_FROM_CART,
  data: data
});

actionsReducer.add = data => ({
  type: constants.ADD_TO_CART,
  data: data
});

actionsReducer.update = data => ({
  type: constants.UPDATE_CART,
  data: data
});

actionsReducer.remove = data => ({
  type: constants.REMOVE_FROM_CART,
  data: data
});

export default actionsReducer;
