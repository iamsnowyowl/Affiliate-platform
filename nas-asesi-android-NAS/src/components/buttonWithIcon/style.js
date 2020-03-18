import { color } from '../../styles/color';

const styles = {
  container: backgroundColor => ({
    width: 50,
    height: 50,
    backgroundColor: backgroundColor,
    borderRadius: 10,
    marginBottom: 10,
    justifyContent: 'center',
    alignItems: 'center'
  }),
  text: {
    textAlign: 'center',
    fontSize: 14,
    color: color.black
  }
};

export default styles;
