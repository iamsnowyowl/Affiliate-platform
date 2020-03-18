import { Dimensions, Platform } from "react-native";
import { color } from "../../../styles/color";

const { width, height } = Dimensions.get("window");

const styles = {
  header_container: {
    width: width,
    height: 40,
    paddingHorizontal: 20,
    borderBottomWidth: 2,
    borderBottomColor: color.blackTransparent,
    justifyContent: "center"
  },
  round_image: {
    width: 80,
    height: 80,
    borderRadius: 50,
    borderWidth: 1,
    borderColor: color.greyPlaceholder,
    overflow: "hidden"
  },
  top_container: {
    flexDirection: "row",
    borderBottomWidth: 1,
    borderColor: color.greyPlaceholder,
    paddingBottom: 10
  },
  btn: {
    borderWidth: 1,
    borderRadius: 5,
    margin: 5,
    marginTop: 10,
    justifyContent: "center",
    height: 30,
    alignItems: "center",
    borderColor: color.darkGrey
  },
  editprofile_btn: {
    flex: 1,
    borderWidth: 1,
    borderRadius: 5,
    margin: 5,
    marginTop: 10,
    justifyContent: "center",
    height: 25,
    alignItems: "center",
    borderColor: color.darkGrey
  },
  title: {
    color: "black",
    fontSize: 16,
    fontWeight: "bold"
  },
  subtitle: {
    color: "black",
    fontSize: 14
  },
  btn_in_modal: {
    backgroundColor: color.green,
    borderRadius: 5,
    width: 80,
    height: 40
  }
};

export default styles;
