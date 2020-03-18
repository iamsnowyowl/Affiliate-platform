import { Dimensions, Platform } from "react-native";
import { color } from "../../../../styles/color";

const { width, height } = Dimensions.get("window");

const styles = {
  header_container: {
    width: width,
    height: 50,
    paddingHorizontal: 20,
    borderBottomWidth: 2,
    borderBottomColor: color.blackTransparent,
    justifyContent: "center"
  },
  round_image: {
    width: 120,
    height: 120,
    borderRadius: 70,
    borderWidth: 1,
    alignSelf: "center",
    borderColor: color.greyPlaceholder,
    overflow: "hidden"
  },
  top_container: {
    flexDirection: "row",
    borderBottomWidth: 1,
    borderColor: color.greyPlaceholder,
    paddingBottom: 20
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
    alignSelf: "center",
    color: "black",
    fontSize: 16,
    fontWeight: "bold"
  },
  subtitle: {
    alignSelf: "center",
    color: "black",
    fontSize: 14
  },
  btn_in_modal: {
    backgroundColor: color.green,
    borderRadius: 5,
    width: 80,
    height: 40
  },
  disableText: {
    height: 50,
    // fontSize: 15,
    // elevation: 5,
    color: "black",
    borderWidth: 1,
    borderRadius: 5,
    // fontWeight: "bold",
    paddingHorizontal: 8,
    paddingVertical: 15,
    borderColor: color.darkGrey,
    backgroundColor: color.greyPlaceholder
  },
  dateBox: {
    height: 45,
    // fontSize: 15,
    // elevation: 5,
    color: "black",
    borderWidth: 1,
    borderRadius: 5,
    paddingHorizontal: 12,
    paddingVertical: 15,
    borderColor: color.darkGrey,
    backgroundColor: "white"
  }
};

export default styles;
