import { Dimensions } from 'react-native';
import { color } from '../../../styles/color';

const { width, height } = Dimensions.get('window');

const styles = {
  container: {
    backgroundColor: color.white,
    width: width,
    paddingBottom: 40
  },
  text: (fontSize, marginBottom, fontWeight, fontColor) => ({
    fontSize: fontSize ? fontSize : 14,
    fontWeight: fontWeight,
    color: fontColor,
    marginBottom: marginBottom ? marginBottom : 0
  })
};

export default styles;
