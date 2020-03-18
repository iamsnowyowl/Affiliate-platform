import { Dimensions, Platform } from "react-native";
import { color } from "../styles/color";

const { width, height } = Dimensions.get("window");

const styles = {
  containerBackground: (alignItems, backgroundColor) => ({
    flex: 1,
    width: width,
    paddingHorizontal: 20,
    overflow: "hidden",
    alignItems: alignItems,
    backgroundColor: backgroundColor
  }),
  cardContainer: (backgroundColor, margin, padding, radius) => ({
    marginBottom: margin,
    width: width - (padding ? padding : 20),
    padding: 20,
    borderRadius: radius,
    elevation: 8,
    backgroundColor: backgroundColor ? backgroundColor : color.white
  }),
  loginIcon: {
    marginTop: 20,
    width: 120,
    height: 120,
    marginBottom: 20
  },
  text: (fontSize, marginBottom, fontWeight, fontColor) => ({
    fontSize: fontSize ? fontSize : 14,
    fontWeight: fontWeight,
    color: fontColor,
    marginBottom: marginBottom ? marginBottom : 0
  }),
  button: {
    elevation: 5,
    backgroundColor: color.green,
    height: 40,
    justifyContent: "center",
    alignItems: "center",
    borderRadius: 5
  },
  borderButton: {
    height: 40,
    width: width - 100,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: color.green,
    borderRadius: 5,
    padding: 10
  },
  textInButton: (fontSize, fontWeight, fontColor) => ({
    fontSize: fontSize ? fontSize : 14,
    fontWeight: fontWeight,
    paddingHorizontal: 15,
    color: fontColor ? fontColor : "#fff"
  })
};

export default styles;
