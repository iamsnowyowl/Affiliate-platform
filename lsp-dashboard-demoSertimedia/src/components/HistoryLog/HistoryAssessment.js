import React, { Component } from "react";
import { Button } from "reactstrap";
import { Input, Icon, Table, Popconfirm, Modal } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import Axios from "axios";
import {
  NotificationContainer,
  NotificationManager
} from "react-notifications";

import {
  path_deletePermanenAssessment,
  baseUrl,
  getData,
  path_restoreAssessment
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";
// import {SearchData} from '../../components/SearchTable/SearchData';

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
import LoadingOverlay from "react-loading-overlay";
const Search = Input.Search;

class HistoryAssessment extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      offset: 0,
      filteredInfo: null,
      searchText: "",
      modal: false,
      modalChangeState: false,
      payload: [],
      payloadUserData: [],
      messageModal: "",
      assessmentID: []
    };
  }

  getColumnSearchProps = dataIndex => ({
    filterDropDown: ({
      setSelectedKeys,
      selectedKeys,
      confirm,
      clearFilters
    }) => (
      <div style={{ padding: 8 }}>
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
          style={{ width: 188, marginBottom: 8, display: "block" }}
        />
        <Button
          type="primary"
          onClick={() => this.handleSearch(selectedKeys, confirm)}
          icon="search"
          size="small"
          style={{ width: 90, marginRight: 8 }}
        >
          {multiLanguage.search}
        </Button>
        <Button
          onClick={() => this.handleReset(clearFilters)}
          size="small"
          style={{ width: 90 }}
        >
          {multiLanguage.reset}
        </Button>
      </div>
    ),
    filterIcon: filtered => (
      <Icon type="search" style={{ color: filtered ? "#1890ff" : undefined }} />
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
        highlightStyle={{ backgroundColor: "#ffc069", padding: 0 }}
        searchWords={[this.state.searchText]}
        autoEscape
        textToHighlight={text.toString()}
      />
    )
  });

  handleSearch = searchText => {
    this.setState({ loading: true });
    const auth = Digest(path_deletePermanenAssessment, "GET");
    reqwest({
      url: baseUrl + path_deletePermanenAssessment + "?search=" + searchText,
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
      const pagination = { ...this.state.pagination };
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
      this.get();
    }
  };

  handleReset = clearFilters => {
    clearFilters();
    this.setState({ searchText: "" });
  };

  get = (params = { sort: "-start_date" }) => {
    this.setState({ loading: true });
    const auth = Digest(path_deletePermanenAssessment, "GET");
    reqwest({
      url: baseUrl + path_deletePermanenAssessment,
      method: "GET",
      data: {
        limit: 10,
        ...params
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    })
      .then(response => {
        const pagination = { ...this.state.pagination };
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
        } else if (error.status) {
          this.errorTrial();
        }
      });
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

  componentDidMount() {
    this.get();
    var json = JSON.parse(localStorage.getItem("userdata"));
    this.setState({
      payloadUserData: json
    });
  }

  handleTableChange = (pagination, filters, sorter) => {
    const pager = { ...this.state.pagination };
    pager.current = pagination.current;
    this.setState({
      pagination: pager
    });
    const offset = (pagination.current - 1) * pagination.pageSize;

    let sorting = "";
    switch (sorter.order) {
      case "ascend":
        sorting = sorter.field;
        break;

      case "descend":
        sorting = "-" + sorter.field;
        break;

      default:
        break;
    }

    this.get({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting
      // sortOrder: sorter.order,
      // ...filters
    });
  };

  deleted = value => {
    this.setState({
      loading: true
    });
    const auth = Digest(path_deletePermanenAssessment + "/" + value, "DELETE");
    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "multipart/form-data"
      },
      url: baseUrl + path_deletePermanenAssessment + "/" + value,
      data: null
    };
    Axios(options).then(() => {
      this.setState({
        loading: false
      });
      this.get();
      // window.location.reload();
    });
  };

  restore = value => {
    this.setState({
      loading: true
    });
    Axios(getData(path_restoreAssessment + "/" + value, "PUT"))
      .then(response => {
        this.setState({
          loading: false
        });
        console.log("response restore", response.data);
      })
      .catch(error => {
        console.log(error.response);
        this.setState({
          loading: false
        });
        NotificationManager.error(
          "Terjadi masalah dalam mengembalikan data",
          "Error",
          5000
        );
      });
  };

  render() {
    const columns = [
      {
        key: "title",
        align: "center",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.assessmentName}
          </h5>
        ),
        dataIndex: "title",
        sorter: true,
        render: value => {
          return <div style={{ textAlign: "left" }}>{value}</div>;
        }
      },
      {
        key: "address",
        align: "center",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.address}
          </h5>
        ),
        width: "20%",
        dataIndex: "address",
        sorter: true,
        render: value => {
          return <div style={{ textAlign: "left" }}>{value}</div>;
        }
      },
      {
        key: "start_date",
        align: "center",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.assessmentDate}
          </h5>
        ),
        dataIndex: "start_date",
        sorter: true,
        render: value => {
          return <div style={{ textAlign: "center" }}>{value}</div>;
        }
      },
      {
        key: "tuk_name",
        align: "center",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.tukName}
          </h5>
        ),
        dataIndex: "tuk_name",
        sorter: true,
        render: value => {
          return <div style={{ textAlign: "center" }}>{value}</div>;
        }
      },
      {
        key: "schema_label",
        align: "center",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.schema}
          </h5>
        ),
        dataIndex: "schema_label",
        sorter: true,
        render: value => {
          return <div style={{ textAlign: "center" }}>{value}</div>;
        }
      },
      {
        key: "assessment_id",
        align: "center",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "assessment_id",
        render: value => {
          console.log("button", value);
          return (
            <Popconfirm
              title={multiLanguage.restoreAlert}
              onConfirm={this.restore.bind(this, value)}
              onCancel={this.cancel}
              okText={multiLanguage.yes}
              cancelText={multiLanguage.no}
            >
              <button
                className="btn btn-primary col-md-auto"
                title={multiLanguage.restore}
              >
                {/* {multiLanguage.restore} */}
                <i class="fa fa-rotate-left" title={multiLanguage.restore}></i>
              </button>
            </Popconfirm>
          );
        }
      }
    ];
    return (
      <div className="animated fadeIn">
        <LoadingOverlay active={this.state.loading} spinner text="Loading...">
          {`${multiLanguage.searching} `}
          <Search
            placeholder={multiLanguage.search}
            onSearch={this.handleSearch}
            onChange={this.handleChange}
            style={{ width: 310 }}
          />{" "}
          <p />
          <Table
            rowKey={record => record.assessment_id}
            columns={columns}
            dataSource={this.state.data}
            pagination={this.state.pagination}
            loading={this.state.loading}
            onChange={this.handleTableChange}
            stripe
          />
        </LoadingOverlay>
        <NotificationContainer />
      </div>
    );
  }
}

export default HistoryAssessment;
