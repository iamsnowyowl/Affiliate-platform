import React from 'react';
import ReactDOM from 'react-dom';
import NewsWebViews from './NewsWebViews';

it('render without crashing', () => {
  const div = document.createElement('div');
  ReactDOM.render(<NewsWebViews />, div);
  ReactDOM.unmountComponentAtNode(div);
});