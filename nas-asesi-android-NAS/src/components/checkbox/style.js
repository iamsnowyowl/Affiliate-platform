import { Dimensions, Platform } from 'react-native';
import { color } from '../../styles/color';

const { width, height } = Dimensions.get('window');

const styles = {
  container: backgroundColor => ({
    width: 25,
    height: 25,
    backgroundColor: backgroundColor,
    padding: 5,
    borderRadius: 4
  }),
  title: {
    color: 'black',
    marginLeft: 10,
    alignSelf: 'center',
    justifyContent: 'center'
  }
};

export default styles;
