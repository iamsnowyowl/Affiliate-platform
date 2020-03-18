import React, { Component } from "react";
import { Row, Col, Card, CardHeader, CardBody, Button } from "reactstrap";
import { Link } from "react-router-dom";
import { Table, Input, Icon, Modal } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import queryString from "query-string";
import LoadingOverlay from "react-loading-overlay";

import {
  path_users,
  baseUrl,
  parseParamsURLquery,
  deleteQueryString
} from "../config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../Language/getBahasa";

import ButtonDelete from "../Button/ButtonDelete";
import ButtonEdit from "../Button/ButtonEdit";

const Search = Input.Search;
const filter = {};

class ListUsers extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      offset: 0,
      filteredInfo: null,
      searchText: ""
    };
  }

  getColumnSearchProps = dataIndex => ({
    filterDropDown: ({
      setSelectedKeys,
      selectedKeys,
      confirm,
      clearFilters
    }) => (
      <div
        style={{
          padding: 8
        }}
      >
        <Input
          ref={node => {
            this.searcInput = node;
          }}
          placeholder={`Search ${dataIndex}`}
          value={selectedKeys[0]}
          onChange={e =>
            setSelectedKeys(e.target.velue ? [e.target.value] : [])
          }
          onPressEnter={() => this.handleSearch(selectedKeys, confirm)}
          style={{
            width: 188,
            marginBottom: 8,
            display: "block"
          }}
        />{" "}
        <Button
          type="primary"
          onClick={() => this.handleSearch(selectedKeys, confirm)}
          icon="search"
          size="small"
          style={{
            width: 90,
            marginRight: 8
          }}
        >
          {multiLanguage.search}{" "}
        </Button>{" "}
        <Button
          onClick={() => this.handleReset(clearFilters)}
          size="small"
          style={{
            width: 90
          }}
        >
          {multiLanguage.reset}{" "}
        </Button>{" "}
      </div>
    ),
    filterIcon: filtered => (
      <Icon
        type="search"
        style={{
          color: filtered ? "#1890ff" : undefined
        }}
      />
    ),
    onFilter: (value, record) =>
      record[dataIndex]
        .toString()
        .toLowerCase()
        .includes(value.toLowerCase()),
    onFilterDropdownVisibleChange: visible => {
      if (visible) {
        setTimeout(() => this.searcInput.select());
      }
    },
    render: text => (
      <Highlighter
        highlightStyle={{
          backgroundColor: "#ffc069",
          padding: 0
        }}
        searchWords={[this.state.searchText]}
        autoEscape
        textToHighlight={text.toString()}
      />
    )
  });

  handleSearch = searchText => {
    var params = {
      search: searchText
    };
    console.log("text", queryString.stringify(params));
    window.location.search = queryString.stringify(params);
    this.setState({
      loading: true
    });
    const auth = Digest(path_users, "GET");
    reqwest({
      url: baseUrl + path_users + "?search=" + searchText,
      method: "GET",
      data: {
        limit: 10
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    }).then(response => {
      const pagination = {
        ...this.state.pagination
      };
      const count = parseInt(response.count, 10);
      pagination.total = count;
      this.setState({
        loading: false,
        data: response.data,
        pagination
      });
    });
  };

  handleChange = event => {
    if (event.target.value === "") {
      console.log("query", deleteQueryString(window.location.href));
      this.setState({
        loading: true
      });
      window.location.replace(deleteQueryString(window.location.href));
    }
  };

  handleReset = clearFilters => {
    clearFilters();
    this.setState({
      searchText: ""
    });
  };

  get = (params = {}) => {
    const urlParams = new URLSearchParams(window.location.search);
    for (let value of urlParams.keys()) {
      filter[value] = urlParams.get(value);
    }
    this.setState({
      loading: true
    });
    const auth = Digest(path_users, "GET");
    reqwest({
      url: baseUrl + path_users,
      method: "GET",
      data: {
        limit: 10,
        ...filter
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    })
      .then(response => {
        const pagination = {
          ...this.state.pagination
        };
        const count = parseInt(response.count, 10);
        pagination.total = count;
        this.setState({
          loading: false,
          data: response.data,
          pagination
        });
      })

      .catch(error => {
        if (error.status === 401) {
          localStorage.clear();
          window.location.replace("/login");
        } else if (error.status === 419) {
          this.errorTrial();
        }
      });
  };

  componentDidMount() {
    this.get();
  }

  handleTableChange = (pagination, filters, sorter) => {
    this.setState({
      loading: true
    });

    const pager = {
      ...this.state.pagination
    };
    pager.current = pagination.current;
    this.setState({
      pagination: pager
    });
    const offset = (pagination.current - 1) * pagination.pageSize;

    var sorting = "";
    var role_code = "";
    if (sorter.order === undefined) {
      sorting = "";
    } else if (sorter.order === "ascend") {
      sorting = sorter.field;
    } else if (sorter.order === "descend") {
      sorting = "-" + sorter.field;
    }

    if (filters.role_code !== undefined) {
      role_code = filters.role_code.join();
    }

    var urlQuery = {
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting,
      role_code: role_code
    };
    window.location.search = queryString.stringify(urlQuery);
    this.get(urlQuery);
  };

  cancel = () => {
    console.log("cancel");
  };

  errorTrial = () => {
    Modal.error({
      title: "Pesan Error",
      content:
        "Masa trial anda telah habis,Harap menghubungi Admin NAS untuk info lebih lanjut",
      onOk() {
        localStorage.clear();
        window.location.replace("/login");
      }
    });
  };

  render() {
    const columns = [
      {
        key: "picture",
        title: (
          <h5
            style={{
              fontWeight: "bold",
              textAlign: "center"
            }}
          >
            {" "}
            {multiLanguage.picture}{" "}
          </h5>
        ),
        dataIndex: "picture",
        render: text => {
          return (
            <img alt="users" src={baseUrl + text + "?width=56&height=56"} />
          );
        }
      },
      {
        key: "username",
        title: (
          <h5
            style={{
              fontWeight: "bold",
              textAlign: "center"
            }}
          >
            {" "}
            Username{" "}
          </h5>
        ),
        dataIndex: "username",
        sorter: true
        // defaultSortOrder:parseParamsURLquery("username")
      },
      {
        key: "email",
        title: (
          <h5
            style={{
              fontWeight: "bold",
              textAlign: "center"
            }}
          >
            {" "}
            Email{" "}
          </h5>
        ),
        dataIndex: "email"
      },
      {
        key: "first_name",
        title: (
          <h5
            style={{
              fontWeight: "bold",
              textAlign: "center"
            }}
          >
            {" "}
            {multiLanguage.name}{" "}
          </h5>
        ),
        dataIndex: "first_name",
        sorter: true,
        render: (text, record) => {
          return (
            <div>
              {" "}
              {text} {record.last_name}{" "}
            </div>
          );
        }
      },
      {
        key: "role_code",
        title: (
          <h5
            style={{
              fontWeight: "bold",
              textAlign: "center"
            }}
          >
            {" "}
            {multiLanguage.role}{" "}
          </h5>
        ),
        dataIndex: "role_code",
        filterMultiple: true,
        filters: [
          {
            text: multiLanguage.assessors,
            value: "ACS"
          },
          {
            text: "Admin LSP",
            value: "ADM"
          },
          {
            text: "Admin TUK",
            value: "ADT"
          },
          {
            text: "Asesi",
            value: "APL"
          },
          {
            text: "Developer",
            value: "DEV"
          },
          {
            text: "Management",
            value: "MAG"
          },
          {
            text: "Super Users",
            value: "SUP"
          }
        ],
        filteredValue:
          parseParamsURLquery("role_code") !== null
            ? parseParamsURLquery("role_code").split(",")
            : [],
        render: role_code => {
          if (role_code === "ACS") {
            return <div> {multiLanguage.assessors} </div>;
          } else if (role_code === "ADM") {
            return <div> Admin LSP </div>;
          } else if (role_code === "ADT") {
            return <div> Admin TUK </div>;
          } else if (role_code === "APL") {
            return <div> Asesi </div>;
          } else if (role_code === "DEV") {
            return <div> Developer </div>;
          } else if (role_code === "MAG") {
            return <div> Management </div>;
          } else {
            return <div> Super Users </div>;
          }
        }
      },
      {
        key: "user_id",
        title: (
          <h5
            style={{
              fontWeight: "bold",
              textAlign: "center"
            }}
          >
            {" "}
            {multiLanguage.action}{" "}
          </h5>
        ),
        dataIndex: "user_id",
        render: value => {
          return (
            <div>
              <ButtonEdit
                url={path_users + "/edit-users/" + value}
                type="edit"
              />{" "}
              <ButtonDelete id_delete={value} path={path_users} />
            </div>
          );
        }
      }
    ];
    const { data, pagination, loading } = this.state;
    return (
      <LoadingOverlay active={this.state.loading} spinner text="Loading..">
        <div className="animated fadeIn">
          <Card>
            <CardHeader>
              <Row>
                <Col md="6">
                  {" "}
                  <h5
                    style={{
                      textDecoration: "underline",
                      color: "navy"
                    }}
                  >
                    {multiLanguage.listUsers}
                  </h5>
                </Col>
                <Col md="6" className="mb-3 mb-xl-0">
                  <Link to={path_users + "/add-users"}>
                    <Button
                      className="float-md-right"
                      size="default"
                      color="primary"
                    >
                      <i className="fa fa-plus" />{" "}
                      {" " + multiLanguage.add + " " + multiLanguage.user}{" "}
                    </Button>{" "}
                  </Link>{" "}
                </Col>{" "}
              </Row>{" "}
            </CardHeader>{" "}
            <CardBody>
              {`${multiLanguage.searching} `}
              <Search
                placeholder={multiLanguage.search}
                onSearch={this.handleSearch}
                onChange={this.handleChange}
                style={{ width: 310 }}
                defaultValue={
                  parseParamsURLquery("search") !== null
                    ? parseParamsURLquery("search")
                    : ""
                }
              />
              <p />
              <Table
                columns={columns}
                rowKey={record => record.user_id}
                dataSource={data}
                pagination={pagination}
                loading={loading}
                onChange={this.handleTableChange}
                striped
              />{" "}
            </CardBody>{" "}
          </Card>{" "}
        </div>
      </LoadingOverlay>
    );
  }
}

export default ListUsers;
