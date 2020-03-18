import constants from "../../constants/constants";

const actionsReducer = {};

actionsReducer.products = data => ({
  type: constants.PRODUCTS,
  data: data
});

actionsReducer.schemas = data => ({
  type: constants.SCHEMAS,
  data: data
});

actionsReducer.tuks = data => ({
  type: constants.TUK_LIST,
  data: data
});

export default actionsReducer;
