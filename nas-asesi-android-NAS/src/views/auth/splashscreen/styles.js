import { Dimensions, Platform } from "react-native";
import { color } from "../../../styles/color";

const { width, height } = Dimensions.get("screen");

const styles = {
  container: {
    width: width,
    height: height,
    justifyContent: "center",
    alignSelf: "center",
    backgroundColor: color.white
  },
  bigIcon: {
    paddingTop: Platform.OS == "ios" ? 20 : 0,
    width: width,
    height: 150,
    justifyContent: "center",
    alignSelf: "center"
  },
  bigTitle: {
    color: color.green,
    fontSize: 30,
    marginBottom: 30,
    fontWeight: "bold",
    justifyContent: "center",
    alignSelf: "center"
  },
  errorText: {
    fontSize: 16,
    textAlign: "center",
    color: color.green,
    fontWeight: "bold",
    marginBottom: 10
  },
  tryAgain: {
    textAlign: "center",
    color: color.green,
    fontWeight: "bold"
  },
  //stepwizard
  stepWizardContainer: {
    width: width,
    height: height,
    paddingTop: Platform.OS == "ios" ? 60 : 20,
    alignSelf: "center",
    backgroundColor: "#f6fff5"
  },
  stepWizardImage: {
    width: width - 20,
    height: 300,
    resizeMode: "contain",
    marginBottom: 20
  },
  stepWizardTitle: {
    paddingHorizontal: 20,
    textAlign: "center",
    color: color.green,
    fontSize: 24,
    fontWeight: "bold"
  },
  stepWizardSubtitle: {
    paddingHorizontal: 40,
    textAlign: "center",
    color: color.green,
    fontSize: 18
  },
  stepWizardButton: {
    height: 40,
    borderRadius: 30,
    justifyContent: "center",
    backgroundColor: color.green
  },
  textInButton: {
    color: "white",
    fontWeight: "bold",
    textAlign: "center",
    fontSize: 20
  }
};

export default styles;
