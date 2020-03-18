import { Dimensions, Platform } from "react-native";
import { color } from "../../styles/color";
const { width, height } = Dimensions.get("window");

const styles = {
  container: backgroundColor => ({
    flexDirection: "row",
    width: width,
    paddingTop: Platform.OS == "ios" ? 25 : 0,
    height: Platform.OS == "ios" ? 75 : 50,
    alignSelf: "center",
    zIndex: 3,
    paddingHorizontal: 20,
    backgroundColor: backgroundColor,
    borderBottomWidth: 2,
    borderBottomColor: color.blackTransparent
  })
};

export default styles;
