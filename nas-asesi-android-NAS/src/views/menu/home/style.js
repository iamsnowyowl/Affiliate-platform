import { Dimensions, Platform } from 'react-native';
import { color } from '../../../styles/color';

const { width, height } = Dimensions.get('window');

const styles = {
  containerBody: {
    width: width - 40,
    height: 200,
    overflow: 'hidden',
    borderRadius: 5
  },
  containerImageBackground: {
    width: width - 40,
    height: 200,
    borderRadius: 5,
    position: 'relative'
  },
  btn: {
    borderWidth: 2,
    borderRadius: 5,
    margin: 5,
    marginTop: 10,
    justifyContent: 'center',
    height: 30,
    alignItems: 'center',
    borderColor: color.darkGrey
  },
  title: {
    color: 'black',
    fontSize: 16,
    fontWeight: 'bold'
  },
  subtitle: {
    color: 'black',
    fontSize: 14
  }
};

export default styles;
