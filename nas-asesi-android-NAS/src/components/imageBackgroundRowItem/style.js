import { Dimensions, Platform } from 'react-native';
import { color } from '../../styles/color';

const { width, height } = Dimensions.get('window');

const styles = {
  containerBody: {
    width: width - 40,
    height: 150,
    marginRight: 15,
    overflow: 'hidden',
    borderRadius: 5
  },
  containerImageBackground: {
    width: width - 20,
    height: 150,
    borderRadius: 5,
    position: 'relative'
  }
};

export default styles;
