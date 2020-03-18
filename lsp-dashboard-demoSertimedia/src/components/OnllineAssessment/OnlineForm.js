import React, { Component } from 'react';
import {
  TabContent,
  TabPane,
  Nav,
  NavItem,
  NavLink,
  Card,
  CardFooter,
  CardBody,
  Button
} from 'reactstrap';
import { Checkbox } from 'antd';
import classnames from 'classnames';
import '../../css/dataRecord.css';
import Bagian1 from './Bagian1';
import Axios from 'axios';
import { getData, path_formOnline } from '../config/config';

const CheckboxGroup = Checkbox.Group;

const plainOptions = [
  'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa',
  'Phasellus viverra nulla ut metus variuslaoreet. Quisque rutrum. Aenean im'
];

export default class OnlineForm extends Component {
  constructor(props) {
    super(props);

    this.state = {
      activeTab: '1',
      tabs: '',
      payload: [],
      checkedList: '',
      indeterminate: false,
      checkAll: false
    };
  }

  componentDidMount() {
    Axios(getData(path_formOnline, 'GET')).then(response => {
      this.setState({
        payload: response.data.data
      });
    });
  }

  toggle = tab => {
    if (this.state.activeTab !== tab) {
      this.setState({
        activeTab: tab
      });
      this.setState({ tabs: tab });
    }
  };

  onChange = checkedList => {
    this.setState({
      checkedList,
      indeterminate:
        !!checkedList.length && checkedList.length < plainOptions.length,
      checkAll: checkedList.length === plainOptions.length
    });
  };

  onCheckAllChange = e => {
    this.setState({
      checkedList: e.target.checked ? plainOptions : [],
      indeterminate: false,
      checkAll: e.target.checked
    });
  };

  render() {
    const {
      payload,
      activeTab,
      checkAll,
      indeterminate,
      checkedList
    } = this.state;
    return (
      <div className="animated fadeIn">
        <Card>
          <Nav tabs>
            <NavItem />
            {payload.map(({ row_id, title }, key) => {
              return (
                <NavItem>
                  <NavLink
                    className={classnames({ aktif: activeTab === row_id })}
                    onClick={() => {
                      this.toggle(row_id);
                    }}
                    key={row_id}
                  >
                    {`Bagian ${row_id}`}
                  </NavLink>
                </NavItem>
              );
            })}
            {/* <NavItem>
              <NavLink
                className={classnames({ aktif: this.state.activeTab === '1' })}
                onClick={() => {
                  this.toggle('1');
                }}
              >
                Bagian 1
              </NavLink>
            </NavItem>
            <NavItem>
              <NavLink
                className={classnames({ aktif: this.state.activeTab === '2' })}
                onClick={() => {
                  this.toggle('2');
                }}
              >
                Bagian 2
              </NavLink>
            </NavItem>
            <NavItem>
              <NavLink
                className={classnames({
                  aktif: this.state.activeTab === '3'
                })}
                onClick={() => {
                  this.toggle('3');
                }}
              >
                Bagian 3
              </NavLink>
            </NavItem> */}
          </Nav>
          <CardBody>
            <TabContent activeTab={activeTab}>
              {payload.map(({ row_id, kuk_id }, key) => {
                if (activeTab === row_id) {
                  return (
                    <div key={kuk_id}>
                      <TabPane className="animated fadeIn" tabId={row_id}>
                        <Bagian1 kuk_id={kuk_id} />
                      </TabPane>
                    </div>
                  );
                } else {
                  return '';
                }
              })}
              {/* {this.state.activeTab === '1' ? (
                <TabPane className="animated fadeIn" tabId="1">
                  <Bagian1 run={1} payload={payload} />
                </TabPane>
              ) : this.state.activeTab === '2' ? (
                <TabPane className="animated fadeIn" tabId="2">
                  Isi 2
                </TabPane>
              ) : (
                <TabPane className="animated fadeIn" tabId="3">
                  Isi 3
                </TabPane>
              )} */}
            </TabContent>
          </CardBody>
          <CardFooter>
            <Button color="primary">Submit</Button>
          </CardFooter>
        </Card>
      </div>
    );
  }
}
