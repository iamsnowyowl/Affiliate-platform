import constants from "../../constants/constants";

const actionsReducer = {};

actionsReducer.assessments = data => ({
  type: constants.ASSESSMENTS,
  data: data
});

actionsReducer.avail_assessments = data => ({
  type: constants.AVAIL_ASSESSMENTS,
  data: data
});

export default actionsReducer;
