import { Dimensions, Platform } from "react-native";
import { color } from "../../styles/color";

const { width, height } = Dimensions.get("window");

const styles = {
  container: {
    backgroundColor: color.white,
    flexDirection: "row",
    width: width - 40,
    elevation: 5,
    height: 100,
    borderRadius: 5,
    justifyContent: "center",
    alignItems: "center",
    position: "relative"
  },
  roundIcon: {
    borderRadius: 5,
    justifyContent: "center",
    alignItems: "center",
    padding: 5,
    backgroundColor: color.green,
    position: "absolute",
    right: 10,
    top: 10
  }
};

export default styles;
