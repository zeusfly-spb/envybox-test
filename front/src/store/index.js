import { createStore } from 'vuex';

const store = createStore({
    state () {
      return {
        currentPoll: null,
        pollStatistics: null,
        voteStatus: ''
      }
    },
    mutations: {
      setCurrentPoll(state, poll) {
        state.currentPoll = poll;
      },
      setPollStatistics(state, statistics) {
        state.pollStatistics = statistics;
      },
      setVoteStatus(state, status) {
        state.voteStatus = status;
      }
    }
  });

  export default store;


  
